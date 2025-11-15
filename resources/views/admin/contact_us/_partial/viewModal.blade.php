<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> {{-- Use modal-lg for more space --}}
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewMessageModalLabel">View Message Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Placeholders for content --}}
            <p><strong>From:</strong> <span id="modalFromName"></span></p>
            <p><strong>Email:</strong> <span id="modalFromEmail"></span></p>
            <p><strong>Phone:</strong> <span id="modalFromPhone"></span></p>
            <p><strong>Received:</strong> <span id="modalReceivedDate"></span></p>
            <hr>
            <p><strong>Message:</strong></p>
            <div id="modalMessageContent" style="white-space: pre-wrap; word-wrap: break-word;"> {{-- Preserve line breaks --}}
                {{-- Message content will be loaded here --}}
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>