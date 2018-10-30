<?php

namespace {{ moduleCamelized }}\Form;

use Zend\Form\Form;
use Zend\Form\ElementSelect;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect vendors information. The form
 * can work in two scenarios - 'create' and 'update'.
 */
class {{ nameCamelized }}Form extends Form
{

    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var DoctrineORMntityManager
     */
    private $entityManager;

    /**
     *
     * @var type
     */
    private $obj;

    /**
     *
     * @var type
     */
    private $objs;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager, $obj = null, $objs = null)
    {
        // Define form name
        parent::__construct('{{ nameDashed }}-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->obj = $obj;
        $this->objs = $objs;

        $this->addElements();
        $this->addInputFilter();
    }


    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

{% for column in columns %}
        // Add "{{ column.name }}" field
{% if column.name matches '/_id$/' %}
        $value = '';
        if ($this->obj->get{{ column.nameCamelizedWithoutId }}()) {
            $value = $this->obj->get{{ column.nameCamelizedWithoutId }}()->getId();
        }
{% endif %}
        $this->add([
            'type' => 'text',
            'name' => '{{ column.name }}',
{% if column.name matches '/_id$/' %}
            'attributes' => ['id' => '{{ column.name }}', 'value' => $value],
            'options' => ['label' => '{{ column.nameWithoutId }}',]
{% else %}
{% if column.type matches '/datetime/' %}
            'attributes' => ['id' => '{{ column.name }}', 'value' => $this->obj->get{{ column.nameCamelized }}YmdHis()],
{% else %}
            'attributes' => ['id' => '{{ column.name }}', 'value' => $this->obj->get{{ column.nameCamelized }}()],
{% endif %}

            'options' => ['label' => '{{ column.nameCamelizedWithSpaces }}',]
{% endif %}

        ]);

{% endfor %}

        // button
        $buttons = array(
            'save',
            'save_and_edit',
            'cancel'
        );
        foreach ($buttons as $button) {
            $value = $button;
            $value = preg_replace('/_/', ' ', $value);
            $value = ucwords($value);
            $this->add([
                'type' => 'submit',
                'name' => $button,
                'attributes' => [
                    'value' => $value
                ],
            ]);
        }

    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $fields = array(
{% for column in columns %}
            '{{ column.name }}' => false,
{% endfor %}
        );
        foreach ($fields as $field => $required) {
            $inputFilter->add([
                'name' => $field,
                'required' => $required
            ]);
        }
    }

}

