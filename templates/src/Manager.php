<?php

namespace {{ moduleCamelized }}\Service;

// this service entity
use {{ moduleCamelized }}\Entity\{{tablenameCamelized}};

//  the service entities that are objects in this entity
{% for column in columns %}
{% if column.name matches '/_id/' %}
use {{ moduleCamelized }}\Entity\{{ column.nameCamelizedWithoutId }};
{% endif %}
{% endfor %}

/**
 * This service is responsible for adding/editing entity.
 */
class {{ nameCamelized }}Manager
{

    /**
     * Doctrine entity manager.
     * @var DoctrineORMntityManager
     */
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {

        $this->entityManager = $entityManager;

    }

    /**
     * This method adds a new entity.
     */
    public function add($data)
    {

        // Create new Location entity.
        $obj = new {{ tablenameCamelized }}();

        // set the object from the data
        $this->setObjectFromData($obj, $data);

        $sub_debug = 0;
        if ($sub_debug) {
            print '<pre>';
            print_r($obj);
            exit;
        }

        // Add the entity to the entity manager.
        $this->entityManager->persist($obj);

        // Apply changes to database.
        $this->entityManager->flush();

        return $obj;
    }

    /**
     * This method updates data of an existing user.
     */
    public function update($obj, $data)
    {

        // set the object from the data
        $this->setObjectFromData($obj, $data);

        $sub_debug = 0;
        if ($sub_debug) {
            print '<pre>';
            print_r($obj);
            exit;
        }

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param type $obj
     * @param type $data
     */
    private function setObjectFromData(&$obj, $data)
    {

{% for column in columns %}
        // set for "{{ column.name }}"
{% if column.name matches '/_id/' %}
        if (isset($data['{{ column.nameWithoutId }}']) && ($data['{{ column.nameWithoutId }}'] != '')) {
            $obj->set{{ column.nameCamelizedWithoutId }}($data['{{ column.nameWithoutId }}']);
        }
        if (isset($data['{{ column.name }}']) && ($data['{{ column.name }}'] != '')) {
            $entity = $this->entityManager->getRepository({{ column.nameCamelizedWithoutId }}::class)->find($data['{{ column.name }}']);
            $obj->set{{ column.nameCamelizedWithoutId }}($entity);
        }
{% else %}
        if (isset($data['{{ column.name }}'])) {
            $obj->set{{ column.nameCamelized }}($data['{{ column.name }}']);
        }
{% endif %}

{% endfor %}
    }

}

