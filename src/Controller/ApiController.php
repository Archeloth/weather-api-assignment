<?php

namespace App\Controller;

use App\Entity\Notice;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use TypeError;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/form", name="api_form", methods={"POST"})
     */
    public function form(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse
    {
        if(!$request) {
            return new JsonResponse([
                'message' => 'Not allowed'
            ], Response::HTTP_FORBIDDEN);
        }
        $entityManager = $doctrine->getManager();

        $notice = new Notice();
        try {
            $notice->setEmail($request->get('notice')['email']);
            $notice->setCity($request->get('notice')['city']);
            $notice->setTempLimit($request->get('notice')['temp_limit']);

            $errors = $validator->validate($notice);
            if (count($errors) > 0) {
                return new JsonResponse([
                    'message' => (string) $errors
                ], Response::HTTP_BAD_REQUEST);
            }

            if($request->get('override') === 'true') {
                $oldNotice = $doctrine->getRepository(Notice::class)->findOneByEmailCity($notice->getEmail(), $notice->getCity());
                if($oldNotice) {
                    $oldNotice->setTempLimit($notice->getTempLimit());
                    $entityManager->persist($oldNotice);
                    $entityManager->flush();

                    return new JsonResponse([
                        'message' => 'Notice successfully overwritten'
                    ], Response::HTTP_OK);
                }
            }

            $entityManager->persist($notice);
            $entityManager->flush();
            return new JsonResponse([
                'message' => 'Notice successfully added'
            ], Response::HTTP_OK);

        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse([
                'message' => 'Notice already exists'
            ], Response::HTTP_CONFLICT);
        } catch (TypeError $e) {
            return new JsonResponse([
                'message' => 'An input field is incorrect'
            ], Response::HTTP_BAD_REQUEST);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'The server ran into an issue'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Route to ping every hour, which checks openweathermap's API and compares it with the database limit values
     * 60 request/minute limit
     * 
     * @Route("/api/cron", name="api_cron")
     */
    public function cron(ManagerRegistry $doctrine, MailerInterface $mailer): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        //API test
        //https://symfony.com/doc/current/doctrine.html#installing-doctrine
        //php bin/console doctrine:query:sql "SELECT * FROM notice"
        
        $notices = $doctrine->getRepository(Notice::class)->findAll();
        $counter = 0;
        $cache = $this->getLocalTemperatureForCities($doctrine); 
        /*[
            'Belgrad' => 8.01,
            'Bucarest' => 9.57,
            'Budapest' => 7.21,
            'London' => 7.84,
            'Madrid' => 16.64,
            'Warsaw' => 3.13,
        ];*/
        
        $now = new \DateTime();
        foreach ($notices as $notice) {
            if($cache[$notice->getCity()] >= $notice->getTempLimit() && ($notice->getEmailSentAt() == null || $notice->getEmailSentAt() < $now->modify("-1 day"))) {
                /*
                $email = (new Email())
                    ->from('quietheroine@gmail.com')
                    ->to($notice->getEmail())
                    ->subject('Test assignment - Weather Notice')
                    ->html('<h1>Weather Notice!</h1><p>The temperature limit you set for the city "<b>'.$notice->getCity().'</b>" reached "<b>'.$notice->getTempLimit().'</b>" C°! Currently its "<b>'.$temperature.'</b>" C°.</p>');
                $mailer->send($email);*/

                $notice->setEmailSentAt($now);
                $entityManager->persist($notice);
                $entityManager->flush();
                $counter++;
            }
        }

        return new JsonResponse([
            'message' => 'Email cron-job finished!',
            'total emails sent' => $counter,
            'local temperatures' => $cache
        ], Response::HTTP_OK);
    }

    private function getLocalTemperatureForCities(ManagerRegistry $doctrine) :array {
        $httpClient = HttpClient::create();
        $cache = [];

        $cities = $doctrine->getRepository(Notice::class)->findEveryCity();

        foreach ($cities as $city) {
            $response = $httpClient->request(
                'GET',
                $this->getParameter('app.api_base_url') . '/data/2.5/weather?q=' . $city['city'] . '&appid=' . $this->getParameter('app.api_key') . '&units=metric'
            );
            $localTemperature = json_decode($response->getContent())->main->temp;
            $cache[$city['city']] = $localTemperature;
        }
        return $cache;
    }
}
