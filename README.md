# Universal Data Transformer

[![Tests](https://github.com/var-lab-it/universal-data-transformer/actions/workflows/ci.yml/badge.svg)](https://github.com/var-lab-it/universal-data-transformer/actions/workflows/ci.yml)

**Universal Data Transformer** is a tool to transform data from the format of one system to another. It was initially 
created to export posts from a MySQL database and create a file from the data that can be imported into a WordPress 
installation.

The aim was to transform the data accordingly via a mapping. Both the mapping and the target formats should be as 
flexibly configurable as possible, with the aim of being able to transform the data for other target systems.

Currently, we work on the first release which will support WordPress.

## Usage

Unfortunately, the tool is not yet available as an executable file. Currently you can run it in an environment where 
PHP scripts can be executed in the console, that can be a Linux system, a Mac or a Windows computer. Please report any
issue with running the tool on your machine.

### Run the tool

You can run the tool with the following command:

```bash
$ php bin/console data:transform <path-to-yaml-config>
```

### Create a configuration
[exampl.yaml](tests%2Ftestfiles%2Fexampl.yaml)
Format: [YAML](https://yaml.org/spec/1.2.2/)

#### Parts of the configuration

**Database**:

Supported database drivers: `pdo_mysql`

| Key             | Example value      | Required? |
|-----------------|--------------------|-----------|
| `driver`        | `pdo_mysql`        | true      | 
| `host`          | `localhost`        | true      |   
| `database_name` | `example_database` | true      |   
| `user`          | `example_user`     | true      |   
| `password`      | `S3CuR3!P4SSW0RD`  | true      |  

#### Example basic configuration

```yaml
database:
  connection:
    driver: pdo_mysql
    host: localhost
    database_name: example_db
    user: test
    password: test
```

## Contribute to the Universal Data Transformer

Please read [CONTRIBUTING.md](CONTRIBUTING.md).

## Contributors

* [Anton Dachauer (var-lab IT)](mailto:ad@var-lab.com)
