<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    ]<style>
        details {
  background-color: #2196F3;
}

details[open] {
  background-color: #ce0e99;
}

summary {
  padding: 10px;
  border-radius: 5px;
}

details[open] summary {
  border-radius: 5px 5px 0 0;
}

details {
  border-radius: 5px;
  margin-bottom: 10px;
  color: white;
}

/* extra styles */

* {box-sizing: border-box;}
body {font-family: system-ui, sans-serif; margin: 20px; background: #fff9f1;}
h1 {font-size: 1.2em;}
article {padding: 20px;}
article > *:first-child {margin: 0;}
article > * + * {margin: 0.75em 0 0 0;}
details code {font-size: 1.1em;}
details a {color: #010b13;}
    </style>
</head>
<body>
<h1>Working with multiple details elements</h1>

<details>
<summary>Click me</summary>
<article>
<p>Unfortunately, it's not currently possible to coordinate multiple <code>details</code> elements in such a way that one closes when another one opens — except via JavaScript.</p>
</article>
</details>

<details>
<summary>Click me</summary>
<article>
<p>But we can style the currently open <code>details</code> element(s) differently from the closed ones.</p>
</article>
</details>

<details>
<summary>Click me</summary>
<article>
<p>You can read about all sorts of ways to style the <code>details</code> element on <a href="https://www.sitepoint.com/style-html-details-element/">SitePoint</a>.</p>
</article>
</details>
</body>
</html>