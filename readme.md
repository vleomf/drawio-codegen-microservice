
### docker-compose.yaml

```yaml
version: '3'
services:
  drawio:
    build:
      context: drawio
      dockerfile: Dockerfile
    ports:
      - 5000:8080
  drawio-codegen:
    build:
      context: drawio-codegen-microservice
      dockerfile: Dockerfile
    volumes:
      - ./drawio-codegen-microservice:/usr/src/app
    ports:
      - 5001:8080
```
