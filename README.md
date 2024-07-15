# Semantic Text Splitter

This is a simple tool to split a text into sentences by semantic meaning.

## Installation

```bash
composer require mathsgod/semantic-splitter-php
```

## Usage

```php

$splitter = new TextSplitter\SemanticTextSplitter(new MyEmbeddingRetriever());

$sentences= $splitter->split("I am a sentence. 
I am another sentence.
I am a sentence that is a question?

這是一個中文句子。
這是另一個中文句子。

如果句子意思接近, 這個工具會把他們放在一起。");

print_r($sentences);

```


### Embedding retriever

Semantic Text Splitter requires an embedding retriever to work. You can implement your own retriever by implementing the `TextSplitter\EmbeddingRetrieverInterface` interface.

```php

class MyEmbeddingRetriever implements TextSplitter\EmbeddingRetrieverInterface
{
    public function getEmbedding(string $text): array
    {
        // Implement your own embedding retriever here
        // for example, you can use OpenAI to get the embedding of the text
        return [0.1, 0.2, 0.3];
    }
}

```






