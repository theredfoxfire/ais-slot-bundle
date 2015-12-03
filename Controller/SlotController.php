<?php

namespace Ais\SlotBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\SlotBundle\Exception\InvalidFormException;
use Ais\SlotBundle\Form\SlotType;
use Ais\SlotBundle\Model\SlotInterface;


class SlotController extends FOSRestController
{
    /**
     * List all slots.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing slots.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many slots to return.")
     *
     * @Annotations\View(
     *  templateVar="slots"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getSlotsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_slot.slot.handler')->all($limit, $offset);
    }

    /**
     * Get single Slot.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Slot for a given id",
     *   output = "Ais\SlotBundle\Entity\Slot",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the slot is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="slot")
     *
     * @param int     $id      the slot id
     *
     * @return array
     *
     * @throws NotFoundHttpException when slot not exist
     */
    public function getSlotAction($id)
    {
        $slot = $this->getOr404($id);

        return $slot;
    }

    /**
     * Presents the form to use to create a new slot.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newSlotAction()
    {
        return $this->createForm(new SlotType());
    }
    
    /**
     * Presents the form to use to edit slot.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisSlotBundle:Slot:editSlot.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the slot id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when slot not exist
     */
    public function editSlotAction($id)
    {
		$slot = $this->getSlotAction($id);
		
        return array('form' => $this->createForm(new SlotType(), $slot), 'slot' => $slot);
    }

    /**
     * Create a Slot from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new slot from the submitted data.",
     *   input = "Ais\SlotBundle\Form\SlotType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisSlotBundle:Slot:newSlot.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postSlotAction(Request $request)
    {
        try {
            $newSlot = $this->container->get('ais_slot.slot.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newSlot->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_slot', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing slot from the submitted data or create a new slot at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\SlotBundle\Form\SlotType",
     *   statusCodes = {
     *     201 = "Returned when the Slot is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisSlotBundle:Slot:editSlot.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the slot id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when slot not exist
     */
    public function putSlotAction(Request $request, $id)
    {
        try {
            if (!($slot = $this->container->get('ais_slot.slot.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $slot = $this->container->get('ais_slot.slot.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $slot = $this->container->get('ais_slot.slot.handler')->put(
                    $slot,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $slot->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_slot', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing slot from the submitted data or create a new slot at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\SlotBundle\Form\SlotType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisSlotBundle:Slot:editSlot.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the slot id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when slot not exist
     */
    public function patchSlotAction(Request $request, $id)
    {
        try {
            $slot = $this->container->get('ais_slot.slot.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $slot->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_slot', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Slot or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return SlotInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($slot = $this->container->get('ais_slot.slot.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $slot;
    }
    
    public function postUpdateSlotAction(Request $request, $id)
    {
		try {
            $slot = $this->container->get('ais_slot.slot.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $slot->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_slot', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
