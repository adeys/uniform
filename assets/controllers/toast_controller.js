import { Controller } from '@hotwired/stimulus';
import { useTransition } from 'stimulus-use';

/* stimulusFetch: 'eager' */
export default class extends Controller {
  static values = {
    delay: { type: Number, default: 4000 },
    autoClose: { type: Boolean, default: true },
    position: { type: String, default: 'top-right' },
  };

  timeout = null;

  connect() {
    console.log('connected');
    if (this.positionValue === 'center') {
      useTransition(this, {
        enterFrom: 'opacity-0 -translate-y-6',
        enterTo: 'opacity-100 translate-y-0',
        leaveFrom: 'opacity-100 translate-y-0',
        leaveTo: 'opacity-0 -translate-y-6',
      });
    } else {
      useTransition(this, {
        enterFrom: 'opacity-0 translate-x-6',
        enterTo: 'opacity-100 translate-x-0',
        leaveFrom: 'opacity-100 translate-x-0',
        leaveTo: 'opacity-0 translate-x-6',
      });
    }

    this.show();
  }

  show() {
    this.enter();

    if (this.autoCloseValue) {
      this.timeout = setTimeout(() => {
        this.close();
      }, this.delayValue);
    }
  }

  async close() {
    if (this.timeout) {
      clearTimeout(this.timeout);
    }

    await this.leave();
    this.element.remove();
  }
}
