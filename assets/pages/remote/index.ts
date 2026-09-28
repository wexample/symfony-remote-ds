import Page from '@wexample/symfony-loader/js/Class/Page';
import RoutingService from '@wexample/symfony-loader/js/Services/RoutingService';
import { unwrapApiEnvelope } from '@wexample/js-api-entity/Common/ApiEnvelope';

type RemoteCheck = {
  state: 'up' | 'down' | 'unconfigured';
  message: string | null;
  latency: number | null;
};

const ROUTE_CHECK = 'api_remote_check';
const STATE_CHECKING = 'checking';
const STATE_DOWN = 'down';

export default class extends Page {
  pageReady() {
    for (const row of this.rows()) {
      row.querySelector('.remote--test')?.addEventListener('click', () => {
        void this.check(row);
      });

      // Each row checks itself: a slow remote delays its own row only.
      void this.check(row);
    }
  }

  private rows(): HTMLElement[] {
    return Array.from(this.el?.querySelectorAll<HTMLElement>('.remote--row') ?? []);
  }

  private async check(row: HTMLElement): Promise<void> {
    const button = row.querySelector<HTMLButtonElement>('.remote--test');
    if (button) {
      button.disabled = true;
    }
    this.render(row, STATE_CHECKING, null, null);

    try {
      const url = (this.app.getService(RoutingService) as RoutingService).path(ROUTE_CHECK, {
        key: row.dataset.remoteKey,
      });
      const response = await fetch(url, { headers: { Accept: 'application/json' } });
      const check = unwrapApiEnvelope<RemoteCheck>(await response.json());

      this.render(row, check.state, check.latency, check.message);
    } catch (error) {
      // The endpoint itself failed (session expired, server down): say so on the row.
      this.render(row, STATE_DOWN, null, error instanceof Error ? error.message : String(error));
    } finally {
      if (button) {
        button.disabled = false;
      }
    }
  }

  private render(row: HTMLElement, state: string, latency: number | null, message: string | null): void {
    const marker = this.el?.querySelector<HTMLTemplateElement>(`.remote--marker[data-state="${state}"]`);
    const stateCell = row.querySelector('.remote--state');

    if (stateCell && marker) {
      stateCell.replaceChildren(marker.content.cloneNode(true));
    }

    row.querySelector('.remote--latency')!.textContent = latency === null ? '' : `${latency.toFixed(1)} ms`;
    row.querySelector('.remote--message')!.textContent = message ?? '';
  }
}
