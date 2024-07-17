<?php

namespace TextSplitter;

class SemanticTextSplitter
{

    protected $embeddingRetriever;
    protected $threshold;

    public function __construct(EmbeddingRetrieverInterface $embeddingRetriever, float $threshold = 0.3)
    {
        $this->embeddingRetriever = $embeddingRetriever;
        $this->threshold = $threshold;
    }

    public function splitTextWithEmbedding(string $text)
    {
        $paragraphs = $this->splitByParagraph($text);

        //trim each paragraph
        $paragraphs = array_map(function ($value) {
            return trim($value);
        }, $paragraphs);

        //filter out empty strings

        $paragraphs = array_filter($paragraphs, function ($value) {
            return $value !== '';
        });

        $final = [];

        foreach ($paragraphs as $paragraph) {
            $final[] = [
                "content" => $paragraph,
                "embedding" => $this->embeddingRetriever->getEmbedding($paragraph)
            ];
        }

        $index = self::find_the_most_similar_index($final, $this->threshold);
        while ($index !== null) {
            $final = self::merge_paragraph($final, $index);
            $index = self::find_the_most_similar_index($final, $this->threshold);
        }

        return $final;
    }

    public function splitText(string $text)
    {
        $data = $this->splitTextWithEmbedding($text);
        return array_column($data, "content");
    }

    private function splitByParagraph($data)
    {
        return preg_split('/\n/', $data);
    }


    private static function add_vector($v1, $v2)
    {
        $result = [];
        for ($i = 0; $i < count($v1); $i++) {
            $result[$i] = $v1[$i] + $v2[$i];
        }
        //normalize the vector
        $sum = 0;
        for ($i = 0; $i < count($result); $i++) {
            $sum += $result[$i] * $result[$i];
        }
        $sum = sqrt($sum);
        for ($i = 0; $i < count($result); $i++) {
            $result[$i] = $result[$i] / $sum;
        }

        return $result;
    }

    private static function find_the_most_similar_index($data, $threshold = 0.3)
    {

        //find the most similar paragraph pair
        // 0 <-> 1, 1<-2, 2<->3, 3<->4, 4<->5, 5<->6, 6<->7, 7<->8, 8<->9, 9<->10
        $max = 0;
        $max_index = null;
        for ($i = 0; $i < count($data) - 1; $i++) {
            $similarity = Util\Similarity::Cosine($data[$i]["embedding"], $data[$i + 1]["embedding"]);
            if ($similarity > $max) {
                $max = $similarity;
                $max_index = $i;
            }
        }

        if ($max <= $threshold) {
            return null;
        }
        return $max_index;
    }

    private static function merge_paragraph($data, $index)
    {
        $data[$index]["content"] .= "\n" . $data[$index + 1]["content"];
        $data[$index]["embedding"] = self::add_vector($data[$index]["embedding"], $data[$index + 1]["embedding"]);
        array_splice($data, $index + 1, 1);
        $data = array_values($data);

        return $data;
    }
}
