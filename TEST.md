Valid URLS
$pattern = '/(?<!\S)(\b(https?|ftp|gemini):\/\/\S+|\S+\.\S+\.\S+)(?!\S)/';

This is an URL: <a href="https://ea.com">https://ea.com</a> <a href="gemini://example.com">gemini://example.com</a> <a href="https://youtu.be/ysaNUatLMn0">https://youtu.be/ysaNUatLMn0</a>

Invalid URL (But marked as valid)
refresh...