<?php

namespace App\Controller;

use App\Entity\Label;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use phpseclib\Net\SSH2;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LabelController extends AbstractController
{
    /**
     * @return string
     */
    public function TestReaderFunction($filename)
    {
        $ssh = new SSH2('urenapi.nl');
        if (!$ssh->login('root', 'L1ndseyT1m')) {
            exit('Login Failed');
        }
        $respone=$ssh->exec('./laundry-symbol-reader/bin/laundry-symbol-reader-dk ClotheCareAPI/public/Labels/'.$filename);

        return new $respone;
    }

    /**
     * @Route("/api/image/upload", name="Photo_upload")
     */
    public function PhotoUpload(Request $request,  ValidatorInterface $validator)
    {

        $data = $request->files->get('data');

        /** @var UploadedFile $file */
        $em = $this->getDoctrine()->getManager();
        $Photo = new Label();
        $file = $data['image'];
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
       try {
            $file->move(
                $this->getParameter('file_directory'),
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $Photo->setImage($fileName);
        $em->persist($Photo);
        $em->flush();
        $responseLine=$this->TestReaderFunction('02.jpeg');
        return new Response(
            json_encode($responseLine)
        );
    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
