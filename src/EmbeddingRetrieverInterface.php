<?php

namespace TextSplitter;

interface EmbeddingRetrieverInterface
{
    /**
     * @param string $text
     * @return array
     */
    public function getEmbedding($text);
}
