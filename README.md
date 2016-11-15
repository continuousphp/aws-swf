# aws-swf

[![Build Status](https://status.continuousphp.com/git-hub/continuousphp/aws-swf?token=7c1624c0-f2da-4c3e-aad5-d032e20c28c3&branch=master)](https://continuousphp.com/git-hub/continuousphp/aws-swf)

# Spaghetti compilation

We would assume you know the basic recipe of cooking spaghetti, if not, it's a good oportunity for your next date!

Here is our application workflow representation :

```
- Cooking Spaghetti ( Workflow )

    - Baking pasta ( Child Workflow )
        - Hot watter ( Activity )
        - Plunge pasta ( Activity )

    - Secret sauce ( Child Workflow )
        - Roast Oignons ( Activity )
        - Tomato Sauce ( Activity )

    - Compile pasta,sauce,parmesan ( Activity )
    - Eat !! ( Activity )
```

# AWS Requirement

In order to run our workflow Spaghetti demo, you need to have an AWS Account.

## Setup IAM

For security reason we recommand you to create a speficic user with appropriate ACL before run this demo.

 1. Connect to your AWS console and go into AWS IAM section.
 2. Create new user named `cphp-aws-swf-demo` with keys assigned.
 2. Copy in your clipboard our policy `config/aws-policy.json`.
 3. Create an `inline policy` under `Permissions` tab on your user.
 4. Select `Custom Policy` and past the json in Policy document and follow instruction.

Now you have an user with proper permission, you need create credential file dedicated to aws keys

```bash
# Use Makefile target for generate file
$ make aws-config
```

```
# Create yourself a file  `.aws.conf` with your keys like this
aws.key=XXXX
aws.secret=XXX
```

## Build the Workflow

Now our project are well setup, we are able to run `phing` task for build
our Domain named `cphp-demo` with our Spaghetti Workflow and Activities associates.

1. Use Makefile for setup AWS SWF

```bash
$ make setup
```

2. Use Phing directly

```bash
# prepare settings for phing task
./vendor/bin/phing -propertyfile .aws.conf build

# run AWS SWF setup process
./vendor/bin/phing -propertyfile .aws.conf init
```


