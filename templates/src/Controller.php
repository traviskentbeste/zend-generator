<?php

namespace {{ moduleCamelized }}\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use {{ moduleCamelized }}\Form\{{ nameCamelized }}Form;
use {{ moduleCamelized }}\Entity\{{ tablenameCamelized }};
{% for column in columns %}
{% if column.name matches '/_id$/' %}
use {{ moduleCamelized }}\Entity\{{ tablenameCamelized }};
{% endif %}
{% endfor %}

/**
 * This controller is responsible for {{ nameCamelized }}
 */
class {{ nameCamelized }}Controller extends AbstractActionController
{

    /**
     * Entity manager.
     * @var DoctrineORMntityManager
     */
    private $entityManager;

    /**
     * {{ nameCamelized }} manager.
     * @var {{ nameCamelized }}Service $nameManager
     */
    private ${{ nameCamelizedLcFirst }}Manager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, ${{ nameCamelizedLcFirst }}Manager)
    {

        $this->entityManager = $entityManager;
        $this->{{ nameCamelizedLcFirst }}Manager = ${{ nameCamelizedLcFirst }}Manager;

    }

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

        $objs = $this->entityManager->getRepository({{ tablenameCamelized }}::class)->findAll();

        // auto add
        //if (!count($objs)) {
        //	return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'add']);
        //}

        return new ViewModel([
            'rows' => $objs,
        ]);

    }

    /**
     * @return ZendHttpResponse|ViewModel
     */
    public function addAction()
    {
        $objs = array();
{% for column in columns %}
{% if column.name matches '/_id$/' %}
        $objs['{{ column.nameWithoutId }}s'] = $this->entityManager->getRepository({{ column.nameCamelized }}::class)->findAll();
{% endif %}
{% endfor %}

        // Create user form
        $form = new {{ nameCamelized }}Form('create', $this->entityManager, new {{ tablenameCamelized }}(), $objs);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            if ((isset($data['cancel'])) && ($data['cancel'] == 'Cancel')) {
                return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'index']);
            }

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                $entity = $this->{{ nameCamelizedLcFirst }}Manager->add($data);

                if (isset($data['save_and_edit']) && ($data['save_and_edit'] == 'Save And Edit')) {
                    return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'edit', 'id' => $entity->getId()]);
                }

                return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'index']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * @return ZendHttpResponse|ViewModel
     */
    public function editAction()
    {

        $id = $this->params()->fromRoute('id', NULL);

        $obj = $this->entityManager->getRepository({{ tablenameCamelized }}::class)->find($id);
        
        $objs = array();
{% for column in columns %}
{% if column.name matches '/_id$/' %}
        $objs['{{ column.nameWithoutId }}s'] = $this->entityManager->getRepository({{ column.nameCamelized }}::class)->findAll();
{% endif %}
{% endfor %}

        // Create form
        $form = new {{ nameCamelized }}Form('update', $this->entityManager, $obj, $objs);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            // redirect if canceled
            if ((isset($data['cancel'])) && ($data['cancel'] == 'Cancel')) {
                return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'index']);
            }

            // set
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // update
                $this->{{ nameCamelizedLcFirst }}Manager->update($obj, $data);

                if (isset($data['save_and_edit']) && ($data['save_and_edit'] == 'Save And Edit')) {
                    return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'edit', 'id' => $obj->getId()]);
                }

                // return
                return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'index']);
            }

        }

        return new ViewModel([
            'form' => $form,
            '{{ nameUnderscored }}' => $obj,
        ]);

    }

    /**
     * @return void|ZendHttpResponse
     */
    public function deleteAction()
    {

        $id = $this->params()->fromRoute('id', NULL);

        if ($id === NULL) {

            $this->getResponse()->setStatusCode(404);

            return;

        }

        // Find entity with id
        $obj = $this->entityManager->getRepository({{ tablenameCamelized }}::class)->find($id);

        if ($obj == null) {

            $this->getResponse()->setStatusCode(404);

            return;

        }

        // remove the object
        $this->entityManager->remove($obj);

        // flush database
            $this->entityManager->flush();

        return $this->redirect()->toRoute('{{ nameDashed }}', ['action' => 'index']);

    }

}

