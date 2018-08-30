<?php

namespace Rebing\GraphQL\Support;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use \Illuminate\Http\UploadedFile;

class UploadType extends ScalarType {

    /**
     * @var string
     */
    public $name = 'Upload';

    /**
     * @var string
     */
    public $description =
        'The `Upload` special type represents a file to be uploaded in the same HTTP request as specified by
 [graphql-multipart-request-spec](https://github.com/jaydenseric/graphql-multipart-request-spec).';

    public function __construct($name = 'Upload')
    {
        $this->name = $name;

        parent::__construct();
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return mixed
     * @throws \Exception
     */
    public function serialize($value)
    {
        throw new InvariantViolation('`Upload` cannot be serialized');
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return mixed
     * @throws \Exception
     */
    public function parseValue($value)
    {
        if (!$value instanceof UploadedFile) {
            throw new \UnexpectedValueException('Could not get uploaded file, be sure to conform to GraphQL multipart request specification. Instead got: ' . Utils::printSafe($value));
        }

        return $value;
    }

    /**
     * @inheritdoc
     *
     * @param Node $valueNode
     * @param array|null $variables
     * @return mixed
     * @throws \Exception
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        throw new Error('`Upload` cannot be hardcoded in query, be sure to conform to GraphQL multipart request specification. Instead got: ' . $valueNode->kind, [$valueNode]);
    }
}
