<?php
/*
 * Vercel serverless PHP entry point.
 * All requests re-enter Laravel through here (routed by vercel.json).
 */

require __DIR__.'/../public/index.php';
