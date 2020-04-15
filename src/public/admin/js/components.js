function Api() {
    this.get = function (apiUrl) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", apiUrl);
            xhr.onload = () => resolve(JSON.parse(xhr.responseText));
            xhr.onerror = () => reject(xhr.statusText);
            xhr.send();
        });
    }
}

var api = new Api();
function Tags(elementId) {
    var rootElement = null;
    if (typeof elementId === 'string') {
        rootElement = document.getElementById(elementId);
    } else {
        rootElement = elementId;
    }

    var formName = rootElement.dataset['name'];
    var allowAddNew = rootElement.dataset['allowaddnew'] ? JSON.parse(rootElement.dataset['allowaddnew']) : false;

    var textInput = rootElement.getElementsByTagName('input')[0];
    var suggestionElement = rootElement.getElementsByTagName('ul')[0];
    var resultPannel = rootElement.getElementsByTagName('div')[0];

    var itemClickLister = null;
    var results = {};
    this.setOnItemClicked = function (c) {
        itemClickLister = c;
    };

    this.hideSuggestion = function () {
        suggestionElement.classList.add('hide');
    };

    this.filter = function (keyword) {
        if (keyword) {
            keyword = keyword.toLowerCase().trim();
        }
        if (!keyword) {
            suggestionElement.classList.add('hide');
            return;
        }
        var total = 0;
        suggestionElement.querySelectorAll('li').forEach(e => {
            var match = e.textContent.toLowerCase().includes(keyword);
            var val = e.dataset['value'];
            if (match && !results[val]) {
                total++;
                e.classList.remove('hide');
            } else {
                e.classList.add('hide');
            }
            if (total) {
                suggestionElement.classList.remove('hide');
            } else {
                suggestionElement.classList.add('hide');
            }
        });
    };

    this.resetText = function () {
        textInput.value = '';
    };

    this.removeItem = function (val) {
        if (!results[val]) return;
        var ele = results[val];
        ele.parentNode.removeChild(ele);
        results[val] = undefined;
    };

    this.addItemEle = function (e) {
        var value = e.dataset['value'];
        var title = e.textContent;
        this.addItem(value, title);
    };

    this.addItem = function (value, title) {
        if (results[value]) return;
        var ele = document.createElement('a');
        ele.innerHTML = `<span>${title}</span><input type="hidden" name="${formName}[]" value="${value}" /> <i class="fa fa-times" aria-hidden="true"></i>`;
        var removeEle = ele.getElementsByTagName('i')[0];

        removeEle.addEventListener('click', (e) => {
            this.removeItem(value);
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
        resultPannel.appendChild(ele);
        results[value] = ele;
        this.hideSuggestion();
        this.resetText();
        textInput.focus();
    };

    this.onEnter = function (e) {
        var text = textInput.value.trim();
        if (text && !suggestionElement.classList.contains('hide')) {
            // add first suggestion element
            var ele = suggestionElement.querySelector("li:not(.hide)");
            this.addItemEle(ele);
        } else if (allowAddNew) {
            this.addItem(text, text);
        }
    };

    for (var i = 0; i < resultPannel.childElementCount; i++) {
        let ele = resultPannel.children[i];
        let removeEle = ele.getElementsByTagName('i')[0];
        let inputEle = ele.getElementsByTagName('input')[0];
        let val = inputEle.value;
        results[val] = ele;
        removeEle.addEventListener('click', (e) => {
            this.removeItem(val);
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    }

    textInput.addEventListener('keyup', (e) => {
        this.filter(textInput.value);
    }, false);

    textInput.addEventListener('keydown', (e) => {
        if (e.keyCode == 13) {
            this.onEnter(e);
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    suggestionElement.querySelectorAll('li').forEach(e => {
        e.addEventListener('click', (e) => {
            this.addItemEle(e.target);
        });
    });
}

// SingleTag Plugin
function SingleTag(elementId) {
    var rootElement = null;
    if (typeof elementId === 'string') {
        rootElement = document.getElementById(elementId);
    } else {
        rootElement = elementId;
    }

    var formName = rootElement.dataset['name'];
    var allowAddNew = rootElement.dataset['allowaddnew'] ? JSON.parse(rootElement.dataset['allowaddnew']) : false;

    var textInput = rootElement.getElementsByTagName('input')[0];
    var suggestionElement = rootElement.getElementsByTagName('ul')[0];
    var resultPannel = rootElement.getElementsByTagName('div')[0];

    var itemClickLister = null;
    var results = {};
    this.setOnItemClicked = function (c) {
        itemClickLister = c;
    };

    this.hideSuggestion = function () {
        suggestionElement.classList.add('hide');
    };

    this.filter = function (keyword) {
        if (keyword) {
            keyword = keyword.toLowerCase().trim();
        }
        if (!keyword) {
            suggestionElement.classList.add('hide');
            return;
        }
        var total = 0;
        suggestionElement.querySelectorAll('li').forEach(e => {
            var match = e.textContent.toLowerCase().includes(keyword);
            var val = e.dataset['value'];
            if (match && !results[val]) {
                total++;
                e.classList.remove('hide');
            } else {
                e.classList.add('hide');
            }
            if (total) {
                suggestionElement.classList.remove('hide');
            } else {
                suggestionElement.classList.add('hide');
            }
        });
    };

    this.resetText = function () {
        textInput.value = '';
    };

    this.removeItem = function (val) {
        if (!results[val]) return;
        var ele = results[val];
        ele.parentNode.removeChild(ele);
        results[val] = undefined;
        textInput.removeAttribute('readonly');
    };

    this.addItemEle = function (e) {
        var value = e.dataset['value'];
        var title = e.textContent;
        this.addItem(value, title);
    };

    this.addItem = function (value, title) {
        if (results[value]) {
            return;
        }
        // only accept 1 item
        if (resultPannel.childElementCount) return;
        var ele = document.createElement('span');
        ele.innerHTML = `<span>${title}</span><input type="hidden" name="${formName}" value="${value}" /> <i class="fa fa-times" aria-hidden="true"></i>`;
        var removeEle = ele.getElementsByTagName('i')[0];

        removeEle.addEventListener('click', (e) => {
            this.removeItem(value);
        });
        resultPannel.appendChild(ele);
        results[value] = ele;
        this.hideSuggestion();
        this.resetText();
        textInput.focus();
        textInput.setAttribute('readonly', 'readonly');
    };

    this.onEnter = function (e) {
        var text = textInput.value.trim();
        if (text && !suggestionElement.classList.contains('hide')) {
            // add first suggestion element
            var ele = suggestionElement.querySelector("li:not(.hide)");
            this.addItemEle(ele);
        } else if (allowAddNew) {
            this.addItem(text, text);
        }
    };

    for (var i = 0; i < resultPannel.childElementCount; i++) {
        let ele = resultPannel.children[i];
        let removeEle = ele.getElementsByTagName('i')[0];
        let inputEle = ele.getElementsByTagName('input')[0];
        let val = inputEle.value;
        results[val] = ele;
        removeEle.addEventListener('click', (e) => {
            this.removeItem(val);
        });
    }

    textInput.addEventListener('keyup', (e) => {
        this.filter(textInput.value);
    }, false);

    textInput.addEventListener('keydown', (e) => {
        if (e.keyCode == 13) {
            this.onEnter(e);
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    suggestionElement.querySelectorAll('li').forEach(e => {
        e.addEventListener('click', (e) => {
            this.addItemEle(e.target);
        });
    });
}

// MSlider plugin
// options: 
// autoplay : true|false
// duration : default: 2000
function MSlider(elementId, options) {
    var mMaxSlider = 0;

    var mOption = {
        autoplay: true,
        duration: 2000
    };

    if (options) {
        Object.assign(mOption, options);
    }

    var mSliderElement = null;
    if (typeof elementId === 'string') {
        mSliderElement = document.getElementById(elementId);
    } else {
        mSliderElement = elementId;
    }

    if (!mSliderElement) return;

    var mSliderScroll = mSliderElement.querySelector('div[mscroller]');
    mMaxSlider = mSliderScroll.childElementCount;

    var mindicatorsElement = mSliderElement.querySelector('div[mindicators]');

    var mSliderControl = mSliderElement.querySelector('div[mcontrols]');

    var currentActive = 1;

    var timer = null;

    this.activeMSlider = function (index) {
        mSliderElement.classList.remove('active' + currentActive);
        mSliderElement.classList.add('active' + index);
        currentActive = index;

        if (mindicatorsElement && mindicatorsElement.childElementCount) {
            for (let i = 0; i < mindicatorsElement.childElementCount; i++) {
                mindicatorsElement.children[i].removeAttribute('active');
            }
            mindicatorsElement.children[index - 1].setAttribute('active', true);
        }
    };

    this.move = function (index) {
        this.activeMSlider(index);
    };

    this.prev = function () {
        var index = currentActive - 1;
        if (index < 1) index = 1;
        this.activeMSlider(index);
    };

    this.next = function () {
        var index = currentActive + 1;
        if (mMaxSlider) {
            if (index > mMaxSlider) {
                index = 1;
            }
        }
        this.activeMSlider(index);
    };

    if (mSliderControl.childElementCount == 2) {
        var btnPrev = mSliderControl.children[0];
        btnPrev.addEventListener('click', (e) => {
            this.prev();
        });

        var btnNext = mSliderControl.children[1];
        btnNext.addEventListener('click', (e) => {
            this.next();
        });
    };

    if (mOption.autoplay) {
        timer = setInterval(() => {
            this.next();
        }, mOption.duration);
    };

    // initial indicators
    if (mindicatorsElement) {
        for (let i = 0; i < mMaxSlider; i++) {
            let span = document.createElement('span');
            span.innerHTML = `<span></span>`;
            mindicatorsElement.appendChild(span);
            span.addEventListener('click', () => {
                this.activeMSlider(i + 1);
            });
        }
    };
}

// MRange plugin
function MRange(elementId, options) {
    var mOptions = {
        value: 0
    };
    if (options) {
        Object.assign(mOptions, options);
    }
    var rangeEle = null;
    if (typeof elementId === 'string') {
        rangeEle = document.querySelector('div[range]');
    } else {
        rangeEle = elementId;
    }

    if (!rangeEle) return;
    if (rangeEle.dataset['value']) {
        var v = parseFloat(rangeEle.dataset['value']);
        if (!isNaN(v)) {
            mOptions.value = v;
        }
    }

    var rangeValueEle = document.createElement('div');
    rangeEle.appendChild(rangeValueEle);

    var rangePointeEle = document.createElement('div');
    rangeEle.appendChild(rangePointeEle);

    var startDrag = false;
    var oldPoint = {
        x: 0,
        y: 0
    };
    var oldValue = 0;
    var listener = null;

    this.getRangeWidth = function () {
        var w = (rangeEle.clientWidth - rangePointeEle.clientWidth);
        return w;
    };

    this.render = function (skip) {
        var p = (rangePointeEle.clientWidth / rangeEle.clientWidth) * 100;

        var newV = (100 - p) / 100 * mOptions.value;

        rangePointeEle.style.left = `${newV}%`;
        rangeValueEle.style.width = `${newV}%`;
        if (skip) return;
        if (listener && typeof listener === 'function') {
            listener(mOptions.value);
        }
    };

    this.setValue = function (v, skip) {
        mOptions.value = v;
        if (mOptions.value < 0) {
            mOptions.value = 0;
        } else if (mOptions.value > 100) {
            mOptions.value = 100;
        }
        this.render(skip);

    };

    this.setOnchangeListener = function (c) {
        listener = c;
    };

    rangeEle.addEventListener('click', (e) => {
        var rangeWidth = this.getRangeWidth();
        mOptions.value = e.offsetX / rangeWidth * 100;
        this.render();
    });

    rangePointeEle.addEventListener('click', (e) => {
        startDrag = false;
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    rangePointeEle.addEventListener('mousedown', (e) => {
        startDrag = true;
        oldPoint.x = e.clientX;
        oldPoint.y = e.clientY;
        oldValue = mOptions.value;
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    document.addEventListener('mouseup', (e) => {
        startDrag = false;
    });

    document.addEventListener('mousemove', (e) => {
        if (!startDrag) return;
        var distanceX = e.clientX - oldPoint.x;
        var rangeWidth = this.getRangeWidth();
        var newValue = oldValue + (distanceX / rangeWidth) * 100;
        this.setValue(newValue);
    });

    this.render();
}

// MYoutubePlayer plugin
function MYoutubePlayer(elementId, options) {
    var mOptions = {
        value: 0
    };
    if (options) {
        Object.assign(mOptions, options);
    }
    var youtubePlayerElement = null;


    if (typeof elementId === 'string') {
        youtubePlayerElement = document.querySelector('div[range]');
    } else {
        youtubePlayerElement = elementId;
    }

    if (!youtubePlayerElement) {
        return;
    }

    var player;

    var overlayElement = youtubePlayerElement.querySelector('div[overlay]');
    var bgElement = youtubePlayerElement.querySelector('div[bg]');
    var controlsElement = youtubePlayerElement.querySelector('div[controls]');
    var progressBarElement = controlsElement.querySelector('div[range]');
    var progressBar = new MRange(progressBarElement);
    var youtubeIframe = youtubePlayerElement.querySelector('iframe');

    var timer = null;

    this.handlePlayVideo = (e) => {
        player.playVideo();
        e.preventDefault();
        e.stopPropagation();

        youtubePlayerElement.setAttribute('showcontrol', true);
        setTimeout(() => {
            youtubePlayerElement.removeAttribute('showcontrol');
        }, 5000);

        return false;
    };

    this.handlePauseVideo = (e) => {
        player.pauseVideo();
    };

    this.fullscreen = (e) => {
        player.playVideo();
        var requestFullScreen = youtubePlayerElement.requestFullScreen || youtubePlayerElement.mozRequestFullScreen || youtubePlayerElement.webkitRequestFullScreen;
        if (requestFullScreen) {
            requestFullScreen.bind(youtubePlayerElement)();
        }
    };

    this.exitFullscreen = (e) => {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    };

    // var playBtn = overlayElement.querySelector('span[play]');
    youtubePlayerElement.querySelectorAll('span[play]').forEach((ele) => {
        ele.addEventListener('click', this.handlePlayVideo);
    });

    youtubePlayerElement.querySelectorAll('span[pause]').forEach((ele) => {
        ele.addEventListener('click', this.handlePauseVideo);
    });

    youtubePlayerElement.querySelectorAll('span[fullscreen]').forEach((ele) => {
        ele.addEventListener('click', this.fullscreen);
    });

    youtubePlayerElement.querySelectorAll('span[exitfullscreen]').forEach((ele) => {
        ele.addEventListener('click', this.exitFullscreen);
    });


    overlayElement.addEventListener('click', this.handlePauseVideo);

    this.onPlayerReady = (event) => {

    };

    this.render = function (playerStatus) {
        var color;
        if (playerStatus == -1) {
            color = "#37474F"; // unstarted = gray
            bgElement.style.display = 'block';
        } else if (playerStatus == 0) {
            color = "#FFFF00"; // ended = yellow
            bgElement.style.display = 'block';
        } else if (playerStatus == 1) {
            color = "#33691E"; // playing = green
            bgElement.style.display = 'none';
        } else if (playerStatus == 2) {
            color = "#DD2C00"; // paused = red
            bgElement.style.display = 'none';
        } else if (playerStatus == 3) {
            color = "#AA00FF"; // buffering = purple
            bgElement.style.display = 'block';
        } else if (playerStatus == 5) {
            color = "#FF6DOO"; // video cued = orange
            bgElement.style.display = 'block';
        }

        if (playerStatus == 1) {
            youtubePlayerElement.setAttribute('playing', true);

            youtubePlayerElement.removeAttribute('pausing');
            timer = setInterval(() => {
                var playerTotalTime = player.getDuration();
                var playerCurrentTime = player.getCurrentTime();

                var v = (playerCurrentTime / playerTotalTime) * 100;
                progressBar.setValue(v, true);
            }, 1000);
        } else {
            youtubePlayerElement.removeAttribute('playing');
            youtubePlayerElement.setAttribute('pausing', true);
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }
    }

    this.onPlayerStateChange = (event) => {
        this.render(event.data);
    };

    this.play = function () {
        if (!player) return;
        player.playVideo();
    };

    player = new YT.Player(youtubeIframe.id, {
        events: {
            'onReady': this.onPlayerReady,
            'onStateChange': this.onPlayerStateChange
        }
    });

    progressBar.setOnchangeListener((v) => {
        var second = player.getDuration() * v / 100;
        player.seekTo(second);
    });

}

// MAutoSuggestion plugin
function MAutoSuggestion(elementId, options) {
    var mOptions = {
        value: 0
    };
    if (options) {
        Object.assign(mOptions, options);
    }
    var rootElement = null;
    if (typeof elementId === 'string') {
        rootElement = document.getElementById(elementId);
    } else {
        rootElement = elementId;
    }

    if (!rootElement) return;

    var inputElement = rootElement.querySelector('input[type=text]');
    var resultPanel = rootElement.querySelector('[autoresult]');
    var timer = null;

    this.render = function(groups) {
        let sTemplate = '';
        groups.forEach(g => {
            if(g.items && g.items.length){
                sTemplate+= `<h4>${g.title}</h4>`;
                sTemplate+= `<ul>`;
                g.items.forEach(i => {
                    sTemplate+= `
                    <li>
                        <a href="${i.url}"><img src="${'/' + i.thumbnail_120}" /> <span>${i.title || i.name}</span></a>
                    </li>
                    `;
                });
                sTemplate+= `</ul>`;
            }
        });

        resultPanel.innerHTML = sTemplate;
    };

    this.handlerSuggestion = () => {
        var value = inputElement.value;
        if(!value) {
            this.render([]);
            return;
        }
        api.get('/suggestion/' + window.encodeURIComponent(value)).then((data) => {
            console.log(data);
            this.render(data);
        });
    };
    inputElement.addEventListener('keyup', (e) => {
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(this.handlerSuggestion, 300);
    });
}

var tags = {};
var singleTags = {};
document.addEventListener("DOMContentLoaded", function () {
    // init tags input
    document.querySelectorAll('div[tags]').forEach((e) => {
        var t = new Tags(e);
        if (e.id) {
            tags[e.id] = t;
        }
    });

    // init tags input
    document.querySelectorAll('div[singletag]').forEach((e) => {
        var t = new SingleTag(e);
        if (e.id) {
            singleTags[e.id] = t;
        }
    });

    document.querySelectorAll('div[mslider]').forEach(e => {
        var t = new MSlider(e);
    });

    document.querySelectorAll('[autosuggestion]').forEach(e => {
        var t = new MAutoSuggestion(e);
    });
});

var mPlayer = null;

function onYouTubeIframeAPIReady() {
    document.querySelectorAll('div[youtube-player]').forEach(e => {
        mPlayer = new MYoutubePlayer(e);
    });
}
// add youtube library
var tag = document.createElement('script');
tag.id = 'iframe-demo';
tag.src = 'https://www.youtube.com/iframe_api';
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// Note that the API is still vendor-prefixed in browsers implementing it
handleFullScreenChange = function (event) {
    console.log('fullscreenchange');
    console.log(event);
    // The event object doesn't carry information about the fullscreen state of the browser,
    // but it is possible to retrieve it through the fullscreen API
    var isFullScreen = document.fullScreen ||
        document.mozFullScreen ||
        document.webkitIsFullScreen;

    if (isFullScreen) {
        document.body.classList.add('fullscreen');
    } else {
        document.body.classList.remove('fullscreen');
    }
};

document.addEventListener("fullscreenchange", handleFullScreenChange);
document.addEventListener("mozfullscreenchange", handleFullScreenChange);
document.addEventListener("webkitfullscreenchange", handleFullScreenChange);