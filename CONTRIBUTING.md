# CONTRIBUTING

_TwbBundle_ is 100% covered by tests. Travis-ci build failed if code coverage is not 100%.

## RUNNING TESTS

To run tests:

- Make sure you have a recent version of PHPUnit installed; 3.7.0
  minimally.
- Enter the `tests/` subdirectory.
- Execute PHPUnit

  ```sh
  % phpunit
  ```

- You may also provide the `--coverage-html` switch;

  ```sh
  % phpunit --coverage-html ./_report
  ```

  This will generate code-coverage report witch can be displayed in a browser by opening `tests/_report/index.html` file