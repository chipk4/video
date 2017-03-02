<?php
namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $itemKey = 'data';

    /**
     * Fractal Transformer instance.
     *
     * @var \League\Fractal\TransformerAbstract
     */
    protected $transformer;

    public function __construct(Request $request)
    {
        if (method_exists($this, 'transformer')) {
            $this->transformer = $this->transformer();
        }
    }

    /**
     * @param $item
     * @param array $additionalFields
     * @return mixed
     */
    protected function respondWithItem($item, $additionalFields = [])
    {
        $resource = $this->transform($item);
        if(is_null($resource)) {
            $resource = [];
        }
        $resultMerged = array_merge($resource, $additionalFields);
        $result[$this->itemKey] = $resultMerged;

        return response()->json($result);
    }

    /**
     * Response with the current error.
     *
     * @param string $error
     * @param integer $statusCode
     *
     * @return mixed
     */
    protected function respondWithError($error, $statusCode = 400)
    {
        return json_encode(['error' => $error]);
    }

    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $result = $this->transformer->transform($item);
        return $result;
    }

    /**
     * @param $transformer
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
    }
}