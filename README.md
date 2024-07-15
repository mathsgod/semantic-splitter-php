# Semantic Text Splitter

This is a simple tool to split a text into sentences by semantic meaning.

## Installation

```bash
composer require mathsgod/semantic-splitter-php
```

## Usage

```php

$splitter = new TextSplitter\SemanticTextSplitter(new EmbeddingRetriever());

$sentences= $splitter->split("I am a sentence. 
I am another sentence.
I am a sentence that is a question?

這是一個中文句子。
這是另一個中文句子。

如果句子意思接近, 這個工具會把他們放在一起。");

print_r($sentences);

```



