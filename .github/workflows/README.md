# GitHub Workflows

## `phpcs.yml`

This GitHub Workflow grants tr1b0t the ability to sniff and inline comment on PRs where the changed PHP code differs from the agreed upon standards.

### Usage

Copy `phpcs.yml.sample` to `phpcs.yml` to enable PHP code sniffing using our own [`action-tribe-phpcs`](https://github.com/moderntribe/action-tribe-phpcs/) GitHub Action.

In order to leverage this code-sniffing workflow, this project will need:

1. A `GH_BOT_TOKEN` secret added to the repo under the project's Settings > Secrets page. The secret can be found in 1Password in the General Systems vault (search for `GH_BOT_TOKEN`).
1. PHP CodeSniffer rules included via composer and declared via a `phpcs.xml` file in the root.

Check out the [`action-tribe-phpcs`](https://github.com/moderntribe/action-tribe-phpcs/) README for more info.

## `eslint.yml`

This GitHub Workflow grants tr1b0t the ability to sniff and inline comment on PRs where the changed JS code differs from the agreed upon standards.

### Usage

Great news! Since we have an `.eslintrc` file, this action is already enabled.

In order to leverage this code-sniffing workflow, this project will need:

1. A `GH_BOT_TOKEN` secret added to the repo under the project's Settings > Secrets page. The secret can be found in 1Password in the General Systems vault (search for `GH_BOT_TOKEN`).
1. A `.eslintrc` file in the root of the project that declares the standards that should be used.
