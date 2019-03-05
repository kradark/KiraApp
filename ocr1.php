namespace Google\Cloud\Samples\Vision;
use Google\Cloud\Vision\V1\ProductSearchClient;
use Google\Cloud\Vision\V1\ProductSet;

/**
 * Create a product set
 *
 * @param string $projectId Your Google Cloud project ID
 * @param string $location Google Cloud compute region name
 * @param string $productSetId ID of the product set
 * @param string $productSetDisplayName Display name of the product set
 */
function product_set_create($projectId, $location, $productSetId, $productSetDisplayName)
{
    $client = new ProductSearchClient();

    # a resource that represents Google Cloud Platform location.
    $locationPath = $client->locationName($projectId, $location);

    # create a product set with the product set specification in the region.
    $productSet = (new ProductSet())
        ->setDisplayName($productSetDisplayName);

    # the response is the product set with the `name` field populated.
    $response = $client->createProductSet($locationPath, $productSet, ['productSetId' => $productSetId]);

    # display the product information.
    printf('Product set name: %s' . PHP_EOL, $response->getName());

    $client->close();
}
