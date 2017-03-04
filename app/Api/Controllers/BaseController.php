<?php
namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $itemKey = 'data';
    protected $itemsKey = 'data';

    /**
     * HTTP header status code.
     *
     * @var int
     */
    protected $statusCode = 200;

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
     * Getter for statusCode.
     *
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode.
     *
     * @param int $statusCode
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $items
     * @return mixed
     */
    public function respondWithItems($items)
    {
        return response()->json(
            [ $this->itemsKey => $this->transformItems($items) ],
            $this->statusCode
        );
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

        return response()->json($result, $this->statusCode);
    }

    /**
     * Response with the current error.
     *
     * @param string $error
     * @param int $errorCode
     *
     * @return mixed
     */
    protected function respondWithError($error, $errorCode = 400)
    {
        $this->setStatusCode($errorCode);

        return $this->formRespond('error', $error);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return mixed
     */
    public function respondWithMessage($message, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);

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
        return response()->json(
            [$respond => $message],
            $this->getStatusCode()
        );
    }
}