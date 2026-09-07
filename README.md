# Description

This repository is a skeleton to start easily a php api based on graphql.
It creates a simple api with a database based on mysql.

```gql
type Query {
  hello: String
  unitTypeById(id: String!): UnitType
  unitTypes: [UnitType]!
}
```

# Getting started

you can simply run the solution with docker and docker-compose.

```bash
docker-compose up
```

This will create the database and feed it with sample data. You can open Apollo studion to test the api on http://localhost:3003/

## API Usage

### Collaborators

#### List collaborators

 ```graphql
  query {
    collaborators {
      id
      firstName
      name
    }
  }
  ```

#### Find a specific collaborator

 ```graphql
  query {
    collaboratorById(id: "some-existing-id") {
      id
      firstName
      name
    }
  }
  ```

### UnitType

#### List UnitTypes

```graphql
query {
  unitTypes {
    id,
    name,
    isSystem
  }
}
```

#### Create a new UnitType

```graphql
mutation {
  unitTypeCreate(input: {
    name: "David"
  }) {
    id
    name
    isSystem
  }
}
```
> [!WARNING]
> Name must be between 1 and 250 chars
