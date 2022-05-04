<?php

namespace App\Controller\Guest;

use App\Entity\Guest\GuestDetail;
use App\Entity\User\User;
use App\Form\Guest\GuestDetailType;
use App\Service\Guest\GuestManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Guest controller.
 *
 */
class GuestController extends AbstractController
{
    /**
     * Creates a new user entity.
     *
     * @Route("/guests", name="guests_list")
     */
    public function index(GuestManager $guestManager)
    {
        /* @var User $user */
        $user = $this->getUser();
        $guestData = $guestManager->getGuestDataByRole(User::ROLE_ADMIN, $user);

        return $this->render('guest/index.html.twig', [
            'guestData' => $guestData
        ]);
    }

    /**
     * Creates a new guest entity.
     *
     * @Route("/guests/entry", name="guest_entry")
     */
    public function entry(Request $request, GuestManager $guestManager)
    {
        $guestDetail = new GuestDetail();
        $form = $this->createForm(GuestDetailType::class, $guestDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guestManager->saveGuestData($form['type']->getData(), $form['imageFile']->getData(),
                $this->getUser(), $guestDetail);

            $this->get('session')->getFlashBag()->set(
                'flashSuccess',
                'Guest detail added successfully.'
            );

            return $this->redirectToRoute('guests_list');
        }

        return $this->render('guest/entry.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit guest detail.
     *
     * @Route("/guests/edit/{id}", name="guest_edit")
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_GUEST')")
     */
    public function edit(Request $request, GuestDetail $guestDetail, GuestManager $guestManager)
    {
        if (!in_array(User::ROLE_ADMIN, $this->getUser()->getRoles())) {
            $this->denyAccessUnlessGranted('own_guest', $guestDetail);
        }
        $form = $this->createForm(GuestDetailType::class, $guestDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guestManager->saveGuestData($form['type']->getData(), $form['imageFile']->getData(),
                $this->getUser(), $guestDetail);

            $this->get('session')->getFlashBag()->set(
                'flashSuccess',
                'Guest detail updated successfully.'
            );

            return $this->redirectToRoute('guests_list');
        }

        return $this->render('guest/edit.html.twig', [
            'form' => $form->createView(),
            'guestDetail' => $guestDetail
        ]);
    }

    /**
     * Creates a new guest entity.
     *
     * @Route("/guests/remove/{id}", name="guest_remove")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function remove(GuestDetail $guestDetail)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($guestDetail);
        $em->flush();

        $this->get('session')->getFlashBag()->set(
            'flashSuccess',
            'Guest detail deleted successfully.'
        );

        return $this->redirectToRoute('guests_list');
    }

    /**
     * update approve status.
     *
     * @Route("/guests/approve/{id}", name="guest_approve")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function approve(GuestDetail $guestDetail, GuestManager $guestManager)
    {
        $guestManager->updateStatus(1, $guestDetail);

        $this->get('session')->getFlashBag()->set(
            'flashSuccess',
            'Guest detail approved successfully.'
        );

        return $this->redirectToRoute('guests_list');
    }

    /**
     * update disapprove status.
     *
     * @Route("/guests/disapprove/{id}", name="guest_disapprove")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function disApprove(GuestDetail $guestDetail, GuestManager $guestManager)
    {
        $guestManager->updateStatus(-1, $guestDetail);

        $this->get('session')->getFlashBag()->set(
            'flashSuccess',
            'Guest detail disapproved successfully.'
        );

        return $this->redirectToRoute('guests_list');
    }

    /**
     * view guest detail.
     *
     * @Route("/guests/view/{id}", name="guest_view")
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_GUEST')")
     */
    public function view(GuestDetail $guestDetail)
    {
        if (!in_array(User::ROLE_ADMIN, $this->getUser()->getRoles())) {
            $this->denyAccessUnlessGranted('own_guest', $guestDetail);
        }

        return $this->render('guest/view.html.twig', [
            'guestDetail' => $guestDetail
        ]);
    }
}
