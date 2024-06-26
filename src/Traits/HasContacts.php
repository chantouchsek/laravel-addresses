<?php

namespace Chantouch\Addresses\Traits;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Collection;
use Chantouch\Addresses\Models\Contact;
use Chantouch\Addresses\Exceptions\FailedValidationException;

/**
 * Class HasContacts
 * @package Chantouch\Addresses\Traits
 * @property Collection|Contact[] $contacts
 */
trait HasContacts
{
    /**
     * Get all contacts for this model.
     *
     * @return MorphMany
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Check if the model has contacts.
     *
     * @return bool
     */
    public function hasContacts(): bool
    {
        return (bool) $this->contacts()->count();
    }

    /**
     * Add a contact to this model.
     *
     * @param array $attributes
     * @return Model
     * @throws Exception
     */
    public function addContact(array $attributes): Model
    {
        $attributes = $this->loadContactAttributes($attributes);

        return $this->contacts()->updateOrCreate($attributes);
    }

    /**
     * Updates the given contact.
     *
     * @param Contact $contact
     * @param array $attributes
     * @return bool
     * @throws Exception
     */
    public function updateContact(Contact $contact, array $attributes): bool
    {
        $attributes = $this->loadContactAttributes($attributes);

        return $contact->fill($attributes)->save();
    }

    /**
     * Deletes given contact.
     *
     * @param Contact $contact
     * @return bool
     * @throws Exception
     */
    public function deleteContact(Contact $contact): bool
    {
        if ($this !== $contact->contactable()->first())
            return false;

        return $contact->delete();
    }

    /**
     * Deletes all the contacts of this model.
     *
     * @return bool
     */
    public function flushContacts(): bool
    {
        return $this->contacts()->delete();
    }

    /**
     * Add country id to attribute an array.
     *
     * @param array $attributes
     * @return array
     * @throws FailedValidationException
     */
    public function loadContactAttributes(array $attributes): array
    {
        // run validation
        $validator = $this->validateContact($attributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $error = '[Addresses] ' . implode(' ', $errors);

            throw new FailedValidationException($error);
        }

        return $attributes;
    }

    /**
     * Validate the contact.
     *
     * @param array $attributes
     * @return Validator
     */
    function validateContact(array $attributes): Validator
    {
        $rules = Contact::getValidationRules();

        return validator($attributes, $rules);
    }
}