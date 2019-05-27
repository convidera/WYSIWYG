<?php

namespace HeinrichConvidera\WYSIWYG\App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UuidModel extends Model
{
    /**
     * Disabling auto increment for identifier
     */
    public $incrementing = false;

    /**
     * Save the model to the database.
     *
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        if ( !$this->id ) {
            $this->id = Uuid::uuid4()->toString();
        }

        return parent::save($options);
    }
}
