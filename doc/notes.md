### Image domain iterator
Removed due to lack of Config Resolver.

Required REST resource: retrieve the list of image variations.

### Upload files dependency on admin-ui
`App\GraphQL\Mutation\UploadFiles` depends on `EzSystems\EzPlatformAdminUi\UI\Config\Provider\ContentTypeMappings`.

Required REST resource: retrieve admin-ui ContentTypes Mappings.

### Image asset resolver
`App\GraphQL\Resolver\ImageAssetFieldResolver` depends on `eZ\Publish\Core\FieldType\ImageAsset\AssetMapper`.

### Input handlers depend on Fields Types services
Most input handlers depend on the each FieldType's service, not part of API.

### RichText converters
Need richtext converters to handle the different formats. Or a REST resource that does the conversion.

### Image variations
Need to implement a variation handler that uses the REST resource to get an image variation.

### Error on /currentversion without an accept header
Returns a 200 from an exception "No view mapping found". Should at least be an error status code.