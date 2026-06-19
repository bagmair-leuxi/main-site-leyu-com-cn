<?php

/**
 * 站点元信息管理类
 * 
 * 用于保存和管理网站的元数据，并提供生成简短描述文本的方法。
 */
class SiteMeta {

    /**
     * @var array 站点元数据集合
     */
    private array $metaData;

    /**
     * 构造函数
     * 
     * @param array $initialData 初始元数据数组（可选）
     */
    public function __construct(array $initialData = []) {
        $this->metaData = $initialData ?: $this->getDefaultMeta();
    }

    /**
     * 获取默认元数据
     * 
     * @return array
     */
    private function getDefaultMeta(): array {
        return [
            'site_name'        => '乐鱼体育',
            'site_url'         => 'https://main-site-leyu.com.cn',
            'site_description' => '乐鱼体育，提供最新体育赛事资讯与互动平台。',
            'site_keywords'    => ['乐鱼体育', '体育资讯', '赛事动态', '运动社区'],
            'author'           => '乐鱼团队',
            'language'         => 'zh-CN',
            'charset'          => 'UTF-8',
            'version'          => '1.0.0',
        ];
    }

    /**
     * 设置元数据项
     * 
     * @param string $key   键名
     * @param mixed  $value 键值
     * @return void
     */
    public function set(string $key, $value): void {
        $this->metaData[$key] = $value;
    }

    /**
     * 获取元数据项
     * 
     * @param string $key     键名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null) {
        return $this->metaData[$key] ?? $default;
    }

    /**
     * 获取所有元数据
     * 
     * @return array
     */
    public function getAll(): array {
        return $this->metaData;
    }

    /**
     * 生成简短的描述文本
     * 
     * 基于站点名称、关键词和描述，生成一段适合用于SEO或分享的简短文本。
     * 
     * @param int $maxLength 最大长度（字符数），默认150
     * @return string
     */
    public function generateShortDescription(int $maxLength = 150): string {
        $name = $this->metaData['site_name'] ?? '';
        $desc = $this->metaData['site_description'] ?? '';
        $keywords = $this->metaData['site_keywords'] ?? [];

        // 构建基础描述
        $base = "欢迎来到{$name}！";
        if (!empty($desc)) {
            $base .= " {$desc}";
        }

        // 添加关键词信息
        if (!empty($keywords)) {
            $keywordStr = implode('、', array_slice($keywords, 0, 3));
            $base .= " 我们关注：{$keywordStr}等话题。";
        }

        // 截取并返回
        if (mb_strlen($base, 'UTF-8') > $maxLength) {
            $base = mb_substr($base, 0, $maxLength - 3, 'UTF-8') . '...';
        }

        return $base;
    }

    /**
     * 生成HTML友好的meta标签字符串
     * 
     * @return string
     */
    public function toHtmlMetaTags(): string {
        $name = htmlspecialchars($this->metaData['site_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($this->metaData['site_description'] ?? '', ENT_QUOTES, 'UTF-8');
        $keywords = htmlspecialchars(implode(',', $this->metaData['site_keywords'] ?? []), ENT_QUOTES, 'UTF-8');
        $url = htmlspecialchars($this->metaData['site_url'] ?? '', ENT_QUOTES, 'UTF-8');

        $html = '';
        $html .= "<meta name=\"description\" content=\"{$desc}\">\n";
        $html .= "<meta name=\"keywords\" content=\"{$keywords}\">\n";
        $html .= "<meta property=\"og:title\" content=\"{$name}\">\n";
        $html .= "<meta property=\"og:description\" content=\"{$desc}\">\n";
        $html .= "<meta property=\"og:url\" content=\"{$url}\">\n";

        return $html;
    }
}

// ==================== 示例用法 ====================
// 此部分仅在直接运行文件时执行
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    // 创建实例
    $siteMeta = new SiteMeta();

    // 输出简短描述
    echo "=== 简短描述 ===\n";
    echo $siteMeta->generateShortDescription(120) . "\n\n";

    // 输出所有元数据
    echo "=== 全部元数据 ===\n";
    print_r($siteMeta->getAll());

    // 输出HTML meta标签
    echo "\n=== HTML Meta 标签 ===\n";
    echo $siteMeta->toHtmlMetaTags();
}