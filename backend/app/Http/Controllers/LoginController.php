
<?php

namespace App\Http\Controllers;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Firebase\JWT\JWT;

class LoginController extends AbstractController
{

    protected $userRepository;
    protected $encoder;
    protected $em;

    public function __construct(
        UserRepository $userRepository, 
        UserPasswordHasherInterface $encoder, 
        EntityManagerInterface $em) {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): Response
    {
        $params = json_decode($request->getContent());
        
        $user = $this->userRepository->findOneBy([
            'email' => $params->email
        ]);

        if($user) {
            if(!$this->encoder->isPasswordValid($user, $params->password)) {
                return new JsonResponse([
                    'message' => 'Usuário ou senha inválidos'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return new JsonResponse([
                'message' => 'Usuário ou senha inválidos'
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getUserIdentifier(),
            'colaborador' => $user->colaborador,
        ];
        
        $token = JWT::encode($data, 'chave');
        
        return new JsonResponse([
            'message' => 'Usuário Logado com Sucesso.',
            'token' => $token,
        ]);
    }

    /**
     * @Route("/me", name="me")
     */
    public function me(Request $request): Response
    {
        $text = $request->headers->get('Authorization');
        $data = JWT::decode($text, 'chave');
        $token = "";
        $data = JWT::decode($token, 'chave');
        
        return new JsonResponse([
            'data' => $data,
        ]);
    }
}
