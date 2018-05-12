<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Writes responses to "storage/framework/testing/disks/{disk}"
 * Example:
 * (new ResponseWriter('disk'))->name(__METHOD__)->handle($response);
 * Writes only responses with 500 code by default, to write other use:
 * (new ResponseWriter('disk'))->codes(500, 422)->name(__METHOD__)->handle($response);
*/
class ResponseWriter
{
    /**
     * Disk name in testing directory
     * @var string
     */
    private $disk;

    /**
     * Filename
     * @var string
     */
    private $name;

    /**
     * Codes to write on
     * @var array
     */
    private $codes = [500];

    public function __construct(string $disk)
    {
        $this->disk = $disk;

        Storage::persistentFake($disk);

        $this->name = class_basename(__METHOD__);
    }

    /**
     * Getter/setter of filename
     * @param string $name
     */
    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }

        $this->name = class_basename($name);

        return $this;
    }

    /**
     * Getter/setter of response codes
     * @param string $name
     */
    public function codes($codes = null)
    {
        if (is_null($codes)) {
            return $this->codes;
        }

        $this->codes = is_int($codes) ? func_get_args() : $codes;

        return $this;
    }

    /**
     * Write responses on bad codes
     * @param \Illuminate\Http\Response  $response
     */
    public function handle($response)
    {
        if (in_array($response->getStatusCode(), $this->codes)) {
            Storage::disk($this->disk)->put(
                $this->name.Carbon::now().'.html',
                $response->getContent()
            );
        }
        return $this;
    }
}
