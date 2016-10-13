<?php
namespace App;
class SocialLogin
{
    /** @var object Database connection */
    private $user;

    /**
     * Instantiate the model class.
     *
     * @param object $db_connection DB connection
     */
    public function __construct($user_model)
    {
        $this->user = $user_model;
    }

    /**
     * Check if a HybridAuth identifier already exists in DB
     *
     * @param int $identifier
     *
     * @return bool
     */
    public function identifier_exists($identifier)
    {
        return ($this->user->where('identifier', '=', $identifier)->count()) > 0;
    }

    /**
     * Save users record to the database.
     *
     * @param string $identifier user's unique identifier
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     * @param string $avatar_url
     *
     * @return bool
     */
    public function register_user( $identifier, $email, $first_name, $last_name, $avatar_url )
    {
        $this->user->create([
            'identifier' => $identifier,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'avatar_url' => $avatar_url
        ]);
    }

    /**
     * Create user login session
     *
     * @param int $identifier
     */
    public function login_user($identifier)
    {
        \Hybrid_Auth::storage()->set('user', $identifier);
    }

    /** Destroy user login session */
    public function logout_user()
    {
        \Hybrid_Auth::storage()->set( 'user', null );
    }
    /**
     * Return user's first name.
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getFirstName( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }

        $user = $this->user->select('first_name')->where('identifier','=', $identifier)->get();
        return $user['first_name'];
    }

    /**
     * Return user's last name.
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getLastName( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $user = $this->user->select('last_name')->where('identifier','=', $identifier)->get();
        return $user['last_name'];
    }

    /**
     * Return user's email address
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getEmail( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $user = $this->user->select('email')->where('identifier','=', $identifier)->get();
        return $user['email'];
    }

    /**
     * Return the URL of user's avatar
     *
     * @param int $identifier
     *
     * @return string
     */
    public function getAvatarUrl( $identifier )
    {
        if ( ! isset( $identifier )) {
            return;
        }
        $user = $this->user->select('avatar_url')->where('identifier','=', $identifier)->get();
        return $user['avatar_url'];
    }
}