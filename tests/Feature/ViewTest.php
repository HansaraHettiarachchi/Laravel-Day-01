<?php

namespace Tests\Feature;

use Tests\TestCase;

class ViewTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function product_list_requires_authentication()
    {
        // Attempt to access product list without authentication
        $response = $this->get('/product-list');

        // Assert redirect to login (or wherever your middleware redirects)
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
