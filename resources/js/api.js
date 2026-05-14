function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? ''
}

async function request(path, { method = 'GET', body, headers = {} } = {}) {
    const finalHeaders = {
        Accept: 'application/json',
        ...headers,
    }

    if (body !== undefined) {
        finalHeaders['Content-Type'] = 'application/json'
        finalHeaders['X-CSRF-Token'] = csrfToken()
    }

    const response = await fetch(path, {
        method,
        headers: finalHeaders,
        body: body !== undefined ? JSON.stringify(body) : undefined,
        credentials: 'same-origin',
    })

    let data = null
    try {
        data = await response.json()
    } catch {
        data = null
    }

    return { status: response.status, ok: response.ok, data }
}

export async function fetchEntries({ page = 1, search = '' } = {}) {
    const params = new URLSearchParams({ page })
    if (search) {
        params.append('search', search)
    }

    const { data } = await request(`/api/entries?${params}`)
    return data ?? { status: 'error', data: [], meta: {} }
}

export function submitEntry(payload) {
    return request('/api/submit', { method: 'POST', body: payload })
}
