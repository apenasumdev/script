Object.defineProperty(Array.prototype, 'move', {
    value: function (old_index, new_index) {
        while (old_index < 0) {
            old_index += this.length;
        }
        while (new_index < 0) {
            new_index += this.length;
        }
        if (new_index >= this.length) {
            let k = new_index - this.length;
            while ((k--) + 1) {
                this.push(undefined);
            }
        }
        this.splice(new_index, 0, this.splice(old_index, 1)[0]);
        return this;
    }
});
window.smoothScroll = () => {
    let currentScroll = document.documentElement.scrollTop || document.body.scrollTop
    if (currentScroll > 0) {
        window.requestAnimationFrame(window.smoothScroll)
        window.scrollTo(0, Math.floor(currentScroll - (currentScroll / 5)))
    }
}
