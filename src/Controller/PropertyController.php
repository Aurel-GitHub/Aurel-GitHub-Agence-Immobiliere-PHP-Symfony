<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository; 
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\Environement;








class PropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $repository;
      /**
     *   
     * @var EntityManagerInterface
     */
     private $em;  

     /**
     * PropertyController constructor.
     * @param PropertyRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/biens", name="property.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {   
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

    
        $properties = $paginator->paginate(
        $this->repository->findAllVisibleQuery($search), 
        $request->query->getInt('page', 1), 12
        );
        
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @param Request $request
     * @param ContactNotification $notifcation
     * @return Response
     */
    public function show(Property $property, string $slug, Request $request, ContactNotification $notifcation): Response
    {

        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug(),
            ], 301);
        }

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notifcation->notify($contact);

            $this->addFlash('succes', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('property.show', [
                'id' =>$property->getId(),
                'slug' =>$property->getSlug()]);
        }

            return $this->render('property/show.html.twig', [
                'property' => $property,
                'current_menu' => 'properties',
                'form' => $form->createView()
            ]);
        }


}