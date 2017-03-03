<?php
namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $itemKey = 'data';
    protected $itemsKey = 'data';

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
     * @param $items
     * @return mixed
     */
    public function respondWithItems($items)
    {
        return response()->json([
            $this->itemsKey => $this->transformItems($items)
        ]);
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
     *
     * @return mixed
     */
    protected function respondWithError($error)
    {
        return $this->formRespond('error', $error);
    }

    /**
     * @param $message
     * @return mixed
     */
    public function respondWithMessage($message)
    {
        return $this->formRespond('message', $message);
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
     * @param $items
     * @return array
     */
    protected function transformItems($items)
    {
        $result = [];
        foreach($items as $item) {
            $result[] = $resource = $this->transform($item);
        }

        return $result;
    }

    /**
     * @param $transformer
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param $respond
     * @param $message
     * @return string
     */
    protected function formRespond($respond, $message) {
        return json_encode([$respond => $message]);
    }
}