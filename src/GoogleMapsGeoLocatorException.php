<?php
/**
 * Created by PhpStorm.
 * User: faramarz
 * Date: 6/1/17
 * Time: 10:07
 */

namespace FSalehpour\GoogleMapsGeolocationAPI;

use FSalehpour\BTSLocator\BTSLocatorException;
use RuntimeException;

class GoogleMapsGeoLocatorException extends RuntimeException implements BTSLocatorException { }