<?php namespace App\Data;

class Lead implements \JsonSerializable {
    private /* array */ $attributes = array() ;

    private $attributeMap = array(
        'Date Received' => 'date_received',
        'Lead Name' => 'name',
        'Email/Phone' => 'email_phone',
        'Source' => 'source',
        'Property Address' => 'property_address',
        'Agent Assigned' => 'agent',
        'Office' => 'office',
        'Notes' => 'notes',
    );

    public function __construct(/* array */ $data) {
        foreach($this->attributeMap as $dataKey => $attributeName) {
            $this->attributes[$attributeName] = '';
            if (array_key_exists($dataKey, $data)) {
                $this->attributes[$attributeName] = $data[$dataKey];
            }
        }
    }

    public function getDate() {
        return $this->attributes['date_received'];
    }

    public function getName() {
        return $this->attributes['name'];
    }

    public function getEmailPhone() {
        return $this->attributes['email_phone'];
    }

    public function getSource() {
        return $this->attributes['source'];
    }

    public function getAddress() {
        return $this->attributes['property_address'];
    }

    public function getAgent() {
        return $this->attributes['agent'];
    }

    public function getOffice() {
        return $this->attributes['office'];
    }

    public function getNotes() {
        return $this->attributes['notes'];
    }

    public function jsonSerialize() {
        return $this->attributes;
    }
}
