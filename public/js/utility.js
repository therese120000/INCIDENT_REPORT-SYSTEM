function formatDateOnly(dateString) {

    if (!dateString) {
        return '-';
    }

    const date = new Date(dateString);

    if (isNaN(date.getTime())) {
        return '-';
    }

    return date.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}