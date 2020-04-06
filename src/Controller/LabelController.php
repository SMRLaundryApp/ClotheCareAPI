<?php

namespace App\Controller;

use App\Entity\Label;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LabelController extends AbstractController
{
    /**
     * @Route("/api/image/upload", name="Photo_upload")
     * @Method("POST")
     */
    public function PhotoUpload(Request $request,  ValidatorInterface $validator)
    {
        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
        $data = json_decode($request->getContent(), true);
        $image = base64_decode($data['image']);
        $location= file_put_contents($filePath, $image);
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $image;
        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Please select a file to upload'
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/*'
                    ]
                ])
            ]
        );
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $em = $this->getDoctrine()->getManager();
        $Photo = new Label();
        $file = $uploadedFile;
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
        return new Response(
            json_encode($uploadedFile)
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
