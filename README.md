# Symfony introduction

## Design Patterns - Challenge I

1. Have a look at the code in `DefaultController`. What do you think of the overall code quality? What could be improved?

2. Try the code with some example URLs. There might have been some mistakes in the code. Fix them.
    
    _Note: This is not a fully working example code. So the FakeRepository will always return the same data._
    * http://refactoring.localtest.me/?file=video_12345_q8c.mp4&directory=video1&quality=q6a&type=video
    * http://refactoring.localtest.me/?file=image_12345.png&directory=image2&type=image

3. Read through: https://refactoring.guru/design-patterns to learn what Design Patterns are and what they are used for.
4. Take a look at the `Strategy`-pattern and the `Factory`-pattern
5. Refactor the code. Use the `Factory`-Pattern to generate your *Data Transfer Object* of your request attributes and the `Strategy`-pattern to create a separation of concerns.
6. Create a MR and discuss the code with your mentor.

Keep in mind to have an eye for a _comprehensible commit history_, coding standards and tests.

## Running the project
```bash
docker-compose run --rm php-cli
$ make install
```

