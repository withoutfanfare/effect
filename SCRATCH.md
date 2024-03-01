# Scratch File

## AWS Credentials

`cat ~/.aws/credentials`

```
[default]
aws_access_key_id = foo
aws_secret_access_key = bar
region = eu-north-1
s3 = endpoint_url = https://s3.uk.cloud.ovh.net
signature_version = s3v4
s3api = endpoint_url = https://s3.uk.cloud.ovh.net
```

## CLI Test

This will pull the text from the PDF and output it to the console as a json object

```bash
aws textract analyze-document --document '{"S3Object":{"Bucket":"stuntrocket-s3","Name":"test.pdf"}}' --feature-types '["TABLES","FORMS","SIGNATURES"]' --profile default --region eu-west-2
```

### CLI Test output

```json
        {
            "BlockType": "LINE",
            "Confidence": 99.8885726928711,
            "Text": "In this technical test, you will create a RESTful endpoint using PHP and a framework of your choice.",
            "Geometry": {
                "BoundingBox": {
                    "Width": 0.7866609692573547,
                    "Height": 0.013556689023971558,
                    "Left": 0.09717720001935959,
                    "Top": 0.2171432226896286
                },
```
