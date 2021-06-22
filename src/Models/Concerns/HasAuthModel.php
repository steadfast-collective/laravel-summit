<?php

namespace SteadfastCollective\Summit\Models\Concerns;

use Exception;

trait HasAuthModel
{
    /**
     * Get the User model class to be used.
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function getAuthModelClass()
    {
        if (config('summit.user_model')) {
            return config('summit.user_model');
        }

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the user model class.');
    }
}
