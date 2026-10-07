 <div class="page reports-page" id="reports">
    <div class="section-card">
        <div class="section-header">
            <div>
                <h2>Incident Reports</h2>
                <p></p>
            </div>
            <!-- <button class="primary-btn">Export Reports</button> -->
        </div>

        <!-- TOOLS -->

        <div class="report-tools">
            <input type="search" placeholder="Search report..." id="reportSearch">
            <select id="issueFilter">
                <option value="">All Issues</option>
                <option>Water Build-up</option>
                <option>Flooding</option>
                <option>Blocked Drainage</option>
                <option>Road Damage</option>
                <option>Garbage</option>
            </select>

            <select id="statusFilter">
                <option value="">All Status</option>
                <option value="SUBMITTED">New Submitted</option>
                <option value="ACKNOWLEDGED">Acknowledged</option>
                <option value="IN_PROGRESS">In Progress</option>
                <option value="RESOLVED">Resolved</option>
            </select>
        </div>

        <!-- REPORT TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Resident ID</th>
                        <th>Issue</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($reports as $report)

                        <tr>
                            <td>{{ $report->report_number }}</td>

                            <td>{{ $report->user_id }}</td>

                            <td>{{ $report->incidentType->name }}</td>

                            <td>{{ $report->purok }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($report->submitted_at)->format('M d, Y') }}
                            </td>

                            <td>
                                <span class="status {{ strtolower(str_replace(' ', '-', $report->status)) }}">
                                    {{ $report->status }}
                                </span>
                            </td>


                            <td>
                                <button
                                    type="button"
                                    class="view-btn"

                                    data-id="{{ $report->id }}"
                                    data-report-number="{{ $report->report_number }}"
                                    data-incident-type="{{ $report->incidentType->name ?? 'Unknown' }}"
                                    data-title="{{ $report->title }}"
                                    data-description="{{ $report->description }}"
                                    data-location="{{ $report->location }}"
                                    data-purok="{{ $report->purok }}"
                                    data-water-level="{{ $report->water_level }}"
                                    data-severity="{{ $report->severity }}"
                                    data-status="{{ $report->status }}"
                                    data-submitted-at="{{ $report->submitted_at }}"
                                >
                                    View
                                </button>
                            </td>
                        
                        </tr>

                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>

<div id="reportStatusModal" class="report-status-modal">

    <div class="report-status-modal-content">

        <div class="modal-header">

            <h2>Report Details</h2>

            <button
                type="button"
                id="closeReportStatusModal"
                class="close-modal-btn"
            >
                &times;
            </button>

        </div>


        <!-- PHOTO -->
         <div class="report-photo-section">

            <div class="report-photo-container">

                <img
                    id="modalReportPhoto"
                    src=""
                    alt="Incident report photo"
                    style="display:none;"
                >

                <div
                    id="noReportPhoto"
                    class="no-report-photo"
                >
                    <div class="photo-placeholder-icon">
                        📷
                    </div>

                    <strong>No Photo Uploaded</strong>

                    <span>
                        No supporting image was attached to this report.
                    </span>
                </div>

            </div>

            <div class="photo-caption">
                <span>Incident Photo</span>
                <small>Supporting evidence submitted with the report</small>
            </div>

        </div>

        <!-- BASIC INFORMATION -->
        <div class="report-details">

            <div class="details-section-title">

                <div>
                    <span class="section-eyebrow">
                        REPORT INFORMATION
                    </span>

                    <h3>Incident Details</h3>
                </div>

            </div>


            <div class="details-grid">

                <!-- REPORT NUMBER -->
                <div class="detail-card">

                    <span class="detail-label">
                        Report Number
                    </span>

                    <span
                        id="modalReportNumber"
                        class="detail-value report-number"
                    >
                        -
                    </span>

                </div>


                <!-- RESIDENT -->
                <div class="detail-card">

                    <span class="detail-label">
                        Resident
                    </span>

                    <span
                        id="modalReportResident"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- INCIDENT TYPE -->
                <div class="detail-card">

                    <span class="detail-label">
                        Incident Type
                    </span>

                    <span
                        id="modalIncidentType"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- TITLE -->
                <div class="detail-card">

                    <span class="detail-label">
                        Report Title
                    </span>

                    <span
                        id="modalReportTitle"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- LOCATION -->   
                <div class="detail-card">

                    <span class="detail-label">
                        Location
                    </span>

                    <span
                        id="modalReportLocation"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- PUROK -->
                <div class="detail-card">

                    <span class="detail-label">
                        Purok
                    </span>

                    <span
                        id="modalReportPurok"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- WATER LEVEL -->
                <div class="detail-card">

                    <span class="detail-label">
                        Water Level
                    </span>

                    <span
                        id="modalReportWaterLevel"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>


                <!-- SEVERITY -->
                <div class="detail-card">

                    <span class="detail-label">
                        Severity
                    </span>

                    <span
                        id="modalReportSeverity"
                        class="detail-value status-badge"
                    >
                        -
                    </span>

                </div>


                <!-- CURRENT STATUS -->
                <div class="detail-card">

                    <span class="detail-label">
                        Current Status
                    </span>

                    <span
                        id="modalReportCurrentStatus"
                        class="detail-value status-badge"
                    >
                        -
                    </span>

                </div>


                <!-- SUBMITTED -->
                <div class="detail-card">

                    <span class="detail-label">
                        Submitted
                    </span>

                    <span
                        id="modalReportSubmittedAt"
                        class="detail-value"
                    >
                        -
                    </span>

                </div>

            </div>


            <!-- DESCRIPTION -->
            <div class="description-card">

                <span class="detail-label">
                    Description
                </span>

                <div
                    id="modalReportDescription"
                    class="description-value"
                >
                    No description provided.
                </div>

            </div>


            <!-- PROGRESS -->
            <div class="report-progress">

                <div class="details-section-title">

                    <div>
                        <span class="section-eyebrow">
                            STATUS HISTORY
                        </span>

                        <h3>Report Progress</h3>
                    </div>

                </div>


                <div id="reportProgressTimeline">

                    <div class="timeline-loading">
                        Loading updates...
                    </div>

                </div>

            </div>

        </div>

        <!-- ADMIN ONLY -->

        <div id="adminReportActions">

            <form id="reportStatusForm">

                <input
                    type="hidden"
                    id="reportId"
                    name="report_id"
                >


                <div class="form-group">

                    <label for="reportStatus">
                        Report Status
                    </label>

                    <select
                        id="reportStatus"
                        name="status"
                        required
                    >

                        <option value="SUBMITTED">
                            Submitted
                        </option>

                        <option value="ACKNOWLEDGED">
                            Acknowledged
                        </option>

                        <option value="IN_PROGRESS">
                            In Progress
                        </option>

                        <option value="RESOLVED">
                            Resolved
                        </option>

                    </select>

                </div>


                <div
                    class="form-group"
                    id="progressMessageGroup"
                    style="display:none;"
                >

                    <label for="progressMessage">
                        Message
                    </label>

                    <textarea
                        id="progressMessage"
                        name="message"
                        rows="4"
                        maxlength="2000"
                        placeholder="Enter the message regarding the progress or resolution..."
                    ></textarea>

                </div>


                <div
                    class="form-group"
                    id="actionTakenGroup"
                    style="display:none;"
                >

                    <label for="actionTaken">
                        Action Taken
                    </label>

                    <textarea
                        id="actionTaken"
                        name="action_taken"
                        rows="4"
                        maxlength="2000"
                        placeholder="Describe the action taken regarding this incident..."
                    ></textarea>

                </div>


                <div class="modal-actions">

                    <button
                        type="button"
                        id="cancelReportStatus"
                        class="cancel-btn"
                    >
                        Close
                    </button>

                    <button
                        type="submit"
                        id="saveReportStatus"
                        class="save-btn"
                    >
                        Update Status
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="{{ asset('js/adminReportStatus.js')}}"></script>
<script src="{{ asset('js/adminReportFilter.js')}}"></script>
    <script src="{{ asset('js/alert.js')}}"></script>

