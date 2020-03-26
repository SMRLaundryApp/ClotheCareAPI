# ======================
# Variables
# ======================

variables:
  REGISTRY_URL: '$CI_REGISTRY/aliter/aliterm/develop:latest'

# ======================
# Stages
# ======================

stages:
  - test
  - build
  - deploy

# ======================
# Cache
# ======================

cache:
  untracked: false
  paths:
    - .cache/
    - node_modules/

# ======================
# Snippets
# ======================

.cache-tool-extract: &cache-tool-extract
  before_script:
    - cache-tool extract yarn:/usr/local/share/.cache/yarn

.cache-tool-collect: &cache-tool-collect
  after_script:
    - cache-tool collect yarn:/usr/local/share/.cache/yarn

.prepare-deploy: &prepare-deploy
  before_script:
    - 'which ssh-agent || (apk add openssh-client)'
    - 'which envsubst || (apk add gettext)'
    - eval $(ssh-agent -s)
    - echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add - > /dev/null
    - mkdir -p ~/.ssh
    - chmod 700 ~/.ssh

# ======================
# Test Code Stage
# ======================

codestyle:
  image: existenz/builder:7.2
  stage: test
  <<: *cache-tool-extract
  script:
    - yarn --no-progress
    - ./node_modules/.bin/eslint . --ext .jsx,.js
  <<: *cache-tool-collect

# ======================
# Build Stage
# ======================

build:
  image: existenz/builder:latest
  stage: build
  services:
    - name: docker:19.03.5-dind
      alias: docker
  variables:
    DOCKER_HOST: tcp://docker:2375
  only:
    - develop@aliter/aliterm
  <<: *cache-tool-extract
  script:
    - yarn
    - docker build -t $REGISTRY_URL .
    - docker login -u $CI_REGISTRY_USER -p $CI_REGISTRY_PASSWORD $CI_REGISTRY
    - docker push $REGISTRY_URL
  <<: *cache-tool-collect
