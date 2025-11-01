<?php

test('two-factor challenge tests skipped after 2FA removal', function () {
    $this->markTestSkipped('Two-factor authentication has been removed from this project.');
});