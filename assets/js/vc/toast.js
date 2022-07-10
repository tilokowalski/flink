
class Toast {
    
    toast;
    autoCloseValue;
    visibleSince;
    progressInterval;
    closeTimeout;

    constructor(options) {
        this.toast = document.createElement("div");

        $(this.toast).addClass("flink-vc");
        $(this.toast).addClass("toast");

        const message = document.createElement("p");
        const title = document.createElement("h1");
        const bar = document.createElement("div");

        $(message).addClass("message");
        $(title).addClass("title");
        $(bar).addClass("bar");

        this.toast.append(bar);
        this.toast.append(title);
        this.toast.append(message);

        $(this.toast).click(() => {
            this.remove();
        });

        requestAnimationFrame(() => {
            $(this.toast).addClass('visible');
        });

        this.visibleSince = new Date();
        this.toast.style.setProperty('--progress', 1);

        this.progressInterval = setInterval(() => {
            const timeVisible = new Date() - this.visibleSince;
            this.toast.style.setProperty('--progress', 1 - timeVisible / this.autoCloseValue);
        }, 10);

        Object.entries(options).forEach(([key, value]) => {
            this[key] = value
        })
    }

    set position(value) {
        const selector = 'div.flink-vc.toast-container[data-position="' + value + '"]';
        const container = $(selector)[0] || createContainer(value);
        container.append(this.toast);
    }

    set autoClose(value) {
        this.autoCloseValue = value;
        if (!value || value === '-1') return;
        const closeTimeout = setTimeout(() => this.remove(), value);
    }

    set type(value) {
        $(this.toast).attr('data-type', value);
    }

    set title(value) {
        $(this.toast).find('h1.title').html(value);
    }

    set message(value) {
        $(this.toast).find('p.message').html(value);
    }

    remove() {
        clearTimeout(this.closeTimeout);
        clearInterval(this.progressInterval);
        const container = $(this.toast).parent();
        $(this.toast).removeClass('visible');
        $(this.toast).on('transitionend', () => {
            $(this.toast).remove();
            if (container.children().length === 0) {
                container.remove();
            }
        });
    }

}

function createContainer(position) {
    const container = document.createElement("div");
    $(container).addClass("flink-vc");
    $(container).addClass("toast-container");
    $(container).attr('data-position', position);
    $('body').append(container);
    return container;
}
