# Contributing

Contributions are **welcome** and will be fully credited.

Please read this guide before creating a issue or opening a pull request.

### Reporting bugs

Please report bugs on GitHub by opening a new issue. To avoid duplicates, please search for an existing bug report and
please only add a new one if none exists for your issue.

### Contribute as a developer

Before submitting a pull request:

Please check the code base by running all code-checks and tests:
* `composer run all-checks` will run
  * PHP code sniffer for style checks
  * `phpstan` for static analysis
  * `phpunit` for unit-testing
  * `phpunit` for integration testing

### Start development

The repository contains a docker setup to getting started with development. Do the following steps to get started:

1. pull the repository
2. run `docker-compose pull` to pull the required images
3. run `docker-compose build` to build the required container(s)
4. run `docker-compose up -d` to start everything
5. open a shell in the container with `docker exec -it universal-data-transformer-php-1 sh`
