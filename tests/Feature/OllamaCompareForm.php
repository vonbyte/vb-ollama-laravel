<?php

it('has ollamacompareform page', function () {
    $response = $this->get('/ollamacompareform');

    $response->assertStatus(200);
});
