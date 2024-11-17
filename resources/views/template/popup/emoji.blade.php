<style>
    .emoji-popup-wrapper {
        display: none; /* Ẩn popup ban đầu */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .emoji-popup-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: auto;
        text-align: center;
        position: relative;
    }
    .emoji-popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 20px;
    }
    .emoji-results-wrapper {
        padding: 10px;
    }
</style>
<!-- 
<div class="emoji-results-wrapper">Clicked emojis: <span id="emojiResults"> </span></div>
<button type="button" class="open_emoji_picker" onclick="openEmojiPopup()">Open Emoji Picker</button> -->

<div class="emoji-popup-wrapper" id="emojiPopup">
    <div class="emoji-popup-content">
        <span class="emoji-popup-close" onclick="closeEmojiPopup()">&times;</span>
        <h3>Emoji Picker</h3>
        <div id="emojiPickerContainer"></div>
    </div>
</div>

<script>
    // Hàm mở popup
    function openEmojiPopup() {
        document.getElementById('emojiPopup').style.display = 'flex';
    }

    // Hàm đóng popup
    function closeEmojiPopup() {
        document.getElementById('emojiPopup').style.display = 'none';
    }
</script>

<script type="module">
    import { Picker } from 'https://esm.sh/emoji-picker-element@1.18.2';

    const picker = new Picker({ locale: 'en' });
    document.getElementById('emojiPickerContainer').appendChild(picker);

    picker.addEventListener('emoji-click', event => {
        $('.editor_atto_content').focus();
        document.execCommand('insertText', false, event.detail.emoji.unicode);
        // const results = document.getElementById('emojiResults');
        // results.innerHTML += event.detail.emoji.unicode;
        closeEmojiPopup();
    });
</script>
