# Grid System

This is a basic grid framework that should be able to power most, if not all of the layouts within your system.

```
<div class="g-row">
    <div class="g-col">{{ component }}</div>
</div>
```

If you want to have a particular amount of columns, but wrap at a certain point:

```
<div class="g-row g-row--col-2">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
```
<div class="g-row g-row--col-3">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
```
<div class="g-row g-row--col-4">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
...

Breakpoint Options:
use helper classes on the parent to adjust your grids by viewport width:

```
<div class="g-row g-row--col-2--min-small g-row--col-4--min-full">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```

```
<div class="g-row g-row--col-2--min-small g-row--col-4">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```

Sidebar Right Layout:

```
<div class="l-weighted-left">
    <div class="c-default">Content</div>
    <div class="c-default">Sidebar</div>
</div>
```

Sidebar Left Layout:

```
<div class="l-weighted-right">
    <div class="c-default">Sidebar</div>
    <div class="c-default">Content</div>
</div>
```

To change the alignment of your columns, add helper classes to the parent:

```
<div class="g-row g-row--center">
    <div class="g-col g-col--one-third">{{ component }}</div>
</div>
```
```
<div class="g-row g-row--pull-right">
    <div class="g-col g-col--one-third">{{ component }}</div>
</div>
```

```
<div class="g-row g-row--col-2 g-row--no-gutters">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
Dynamic columns: These are mostly for when you have a particular layout that calls for a column with a defined width.

```
<div class="g-row">
    <div class="g-col g-col--one-fourth">{{ component }}</div>
</div>
```
Optional classes include: ```g-col--one-fourth, g-col--one-third, g-col--one-half, g-col--three-fourths```